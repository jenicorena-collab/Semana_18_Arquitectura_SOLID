<?php
namespace Tests\Feature;
use App\Models\Comercio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
class RegistrarTransaccionTest extends TestCase
{
    use RefreshDatabase;
    public function test_registra_transaccion_con_comision_de_tarjeta(): void
    {
        $user=User::factory()->create(); $comercio=Comercio::factory()->create(['user_id'=>$user->id]); Sanctum::actingAs($user);
        $this->postJson('/api/transacciones',['comercio_id'=>$comercio->id,'monto'=>100,'metodo'=>'tarjeta'])
            ->assertCreated()->assertJsonPath('data.estado','Iniciada');
        $this->assertDatabaseHas('transacciones',['comercio_id'=>$comercio->id,'comision'=>'3.50']);
    }
    public function test_comision_fija_en_transferencia(): void
    {
        $u=User::factory()->create();$c=Comercio::factory()->create(['user_id'=>$u->id]);Sanctum::actingAs($u);
        $this->postJson('/api/transacciones',['comercio_id'=>$c->id,'monto'=>100,'metodo'=>'transferencia'])->assertCreated();
        $this->assertDatabaseHas('transacciones',['comercio_id'=>$c->id,'comision'=>'1.50']);
    }
    public function test_comision_de_billetera_es_dos_por_ciento(): void
    {
        $u=User::factory()->create();$c=Comercio::factory()->create(['user_id'=>$u->id]);Sanctum::actingAs($u);
        $this->postJson('/api/transacciones',['comercio_id'=>$c->id,'monto'=>100,'metodo'=>'billetera'])->assertCreated();
        $this->assertDatabaseHas('transacciones',['comercio_id'=>$c->id,'comision'=>'2.00']);
    }
    public function test_comercio_ajeno_devuelve_404(): void
    {
        $u=User::factory()->create();$otro=User::factory()->create();$c=Comercio::factory()->create(['user_id'=>$otro->id]);Sanctum::actingAs($u);
        $this->postJson('/api/transacciones',['comercio_id'=>$c->id,'monto'=>100,'metodo'=>'tarjeta'])->assertNotFound();
    }
    public function test_datos_invalidos_devuelven_422(): void
    {
        $u=User::factory()->create();Sanctum::actingAs($u);
        $this->postJson('/api/transacciones',['comercio_id'=>null,'monto'=>-5,'metodo'=>'cripto'])
            ->assertUnprocessable()->assertJsonValidationErrors(['comercio_id','monto','metodo']);
    }
    public function test_sin_token_devuelve_401(): void
    {
        $this->postJson('/api/transacciones',['comercio_id'=>1,'monto'=>100,'metodo'=>'tarjeta'])->assertUnauthorized();
    }

    public function test_actualiza_estado_de_transaccion_iniciada(): void
    {
        $u=User::factory()->create();$c=Comercio::factory()->create(['user_id'=>$u->id]);Sanctum::actingAs($u);
        $response=$this->postJson('/api/transacciones',['comercio_id'=>$c->id,'monto'=>100,'metodo'=>'tarjeta'])->assertCreated();
        $id=$response->json('data.id');
        $this->patchJson('/api/transacciones/'.$id,['estado'=>'Completada'])->assertOk()->assertJsonPath('data.estado','Completada');
        $this->assertDatabaseHas('evento_transacciones',['transaccion_id'=>$id,'tipo'=>'cambio_estado']);
    }
    public function test_no_permite_cambiar_estado_de_una_transaccion_cerrada(): void
    {
        $u=User::factory()->create();$c=Comercio::factory()->create(['user_id'=>$u->id]);Sanctum::actingAs($u);
        $response=$this->postJson('/api/transacciones',['comercio_id'=>$c->id,'monto'=>100,'metodo'=>'tarjeta'])->assertCreated();
        $id=$response->json('data.id');
        $this->patchJson('/api/transacciones/'.$id,['estado'=>'Completada'])->assertOk();
        $this->patchJson('/api/transacciones/'.$id,['estado'=>'Fallida'])->assertUnprocessable();
    }
    public function test_no_actualiza_transaccion_de_otro_usuario(): void
    {
        $owner=User::factory()->create();$other=User::factory()->create();$c=Comercio::factory()->create(['user_id'=>$owner->id]);
        Sanctum::actingAs($owner);
        $created=$this->postJson('/api/transacciones',['comercio_id'=>$c->id,'monto'=>100,'metodo'=>'tarjeta'])->assertCreated();
        $id=$created->json('data.id');
        Sanctum::actingAs($other);
        $this->patchJson('/api/transacciones/'.$id,['estado'=>'Completada'])->assertNotFound();
    }

}
