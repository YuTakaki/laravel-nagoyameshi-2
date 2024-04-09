<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RestaurantTest extends TestCase
{
    use RefreshDatabase;

    //未ログインのユーザーは管理者側の店舗一覧ページにアクセスできない
    public function test_unauthenticated_user_cannnot_access_admin_restaurant_list_page(): void 
    {
        $response = $this->get('admin.restaurants.index');

        $response->assertStatus(404);
    }
    
    // ログイン済みの一般ユーザーは管理者側の店舗一覧ページにアクセスできない
    public function test_authenticated_regular_user_cannot_access_admin_restaurant_list_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('admin.restaurants.index');

        $response->assertStatus(404);
    }

    // ログイン済みの管理者は管理者側の店舗一覧ページにアクセスできる
    public function test_authenticated_admin_can_access_admin_restaurant_list_page(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $restaurant = Restaurant::factory()->create();
    
        // 管理者としてログイン
        $this->actingAs($admin);
    
        // 会員の詳細ページにアクセス
        $response = $this->get(route('admin.restaurants.index', ['restaurant' => $restaurant]));
    
        // リダイレクトされることを確認
        $response->assertStatus(302);
    }

    // 未ログインのユーザーは管理者側の店舗詳細ページにアクセスできない
    public function test_unauthenticated_user_cannnot_access_admin_restaurant_detail_page(): void
    {
        $response = $this->get('admin.restaurants.show');

        $response->assertStatus(404);
    }

    // ログイン済みの一般ユーザーは管理者側の店舗詳細ページにアクセスできない
    public function test_authenticated_regular_user_cannot_access_admin_restaurant_detail_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('admin.restaurants.show');

        $response->assertStatus(404);
    }

    // ログイン済みの管理者は管理者側の店舗詳細ページにアクセスできる
    public function test_authenticated_admin_can_access_admin_restaurant_detail_page(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $restaurant = Restaurant::factory()->create();
    
        // 管理者としてログイン
        $this->actingAs($admin);
    
        // 店舗の詳細ページにアクセス
        $response = $this->get(route('admin.restaurants.show', ['restaurant' => $restaurant]));
    
        // リダイレクトされることを確認
        $response->assertStatus(302);
    }

    // 未ログインのユーザーは管理者側の店舗登録ページにアクセスできない
    public function test_unauthenticated_user_cannnot_access_admin_restaurant_register_page(): void 
    {
        $response = $this->get('admin.restaurants.create');

        $response->assertStatus(404);
    }

    // ログイン済みの一般ユーザーは管理者側の店舗登録ページにアクセスできない
    public function test_authenticated_regular_user_cannot_access_admin_restaurant_register_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('admin.restaurants.create');

        $response->assertStatus(404);
    }

    // ログイン済みの管理者は管理者側の店舗登録ページにアクセスできる
    public function test_authenticated_admin_can_access_admin_restaurant_register_page(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $restaurant = Restaurant::factory()->create();
    
        // 管理者としてログイン
        $this->actingAs($admin);
    
        // 会員の詳細ページにアクセス
        $response = $this->get(route('admin.restaurants.create', ['restaurant' => $restaurant]));
    
        // リダイレクトされることを確認
        $response->assertStatus(302);
    }

    // 未ログインのユーザーは店舗を登録できない
    public function test_unauthenticated_user_cannnot_register_restaurant(): void
    {
        $restaurant = Restaurant::factory()->create();
        $data = $restaurant->toArray();

        $response = $this->post(route('admin.restaurants.store'), $data);

        $this->assertDatabaseMissing('restaurants', $data);
        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの一般ユーザーは店舗を登録できない
    public function test_authenticated_regular_user_cannot_register_restaurant(): void
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $data = $restaurant->toArray();

        $response = $this->actingAs($user)->post(route('admin.restaurants.store'), $data);
 
        $this->assertDatabaseMissing('restaurants', $data);
        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの管理者は店舗を登録できる
    public function test_authenticated_admin_can_register_restaurant(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $restaurant = Restaurant::factory()->create();
        $data = $restaurant->toArray();

        unset($data['created_at'], $data['updated_at']);

        $this->actingAs($admin);
        $response = $this->post(route('admin.restaurants.store'), $data);

        $this->assertDatabaseHas('restaurants', $data);
        $response->assertStatus(302);
        }

    // 未ログインのユーザーは管理者側の店舗編集ページにアクセスできない
    public function test_unauthenticated_user_cannnot_access_admin_restaurant_edit_page(): void 
    {
        $response = $this->get('admin.restaurants.edit');

        $response->assertStatus(404);
    }

    // ログイン済みの一般ユーザーは管理者側の店舗編集ページにアクセスできない
    public function test_authenticated_regular_user_cannot_access_admin_restaurant_edit_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('admin.restaurants.edit');

        $response->assertStatus(404);
    }

    // ログイン済みの管理者は管理者側の店舗編集ページにアクセスできる
    public function test_authenticated_admin_can_access_admin_restaurant_edit_page(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $restaurant = Restaurant::factory()->create();
    
        // 管理者としてログイン
        $this->actingAs($admin);
    
        // 店舗の編集ページにアクセス
        $response = $this->get(route('admin.restaurants.edit', ['restaurant' => $restaurant]));
    
        // リダイレクトされることを確認
        $response->assertStatus(302);
    }

    // 未ログインのユーザーは店舗を更新できない
    public function test_unauthenticated_user_cannnot_update_restaurant(): void
    {
        $old_restaurant = Restaurant::factory()->create();
        $new_restaurant = Restaurant::factory()->create();

        $new_data = $new_restaurant->toArray();

        $response = $this->patch(route('admin.restaurants.update', $old_restaurant), $new_data);

        $this->assertDatabaseMissing('restaurants', $new_data);
        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの一般ユーザーは店舗を更新できない
    public function test_authenticated_regular_user_cannot_update_restaurant(): void
    {
        $user = User::factory()->create();
        $old_restaurant = Restaurant::factory()->create();
        $new_restaurant = Restaurant::factory()->create();

        $new_data = $new_restaurant->toArray();

        $response = $this->actingAs($user)->patch(route('admin.restaurants.update', $old_restaurant), $new_data);

        $this->assertDatabaseMissing('restaurants', $new_data);
        $response->assertRedirect(route('admin.login'));

    }

    // ログイン済みの管理者は店舗を更新できる
    public function test_authenticated_admin_can_update_restaurant(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $old_restaurant = Restaurant::factory()->create();
        $new_restaurant = Restaurant::factory()->create();
        $new_data = $new_restaurant->toArray();

        unset($new_data['created_at'], $new_data['updated_at']);

        $response = $this->actingAs($admin)->patch(route('admin.restaurants.update', $old_restaurant), $new_data);

        $this->assertDatabaseHas('restaurants', $new_data);
        $response->assertStatus(302);
    }

    // 未ログインのユーザーは店舗を削除できない
    public function test_unauthenticated_user_cannnot_delete_restaurant(): void
    {
        $restaurant = Restaurant::factory()->create();
        $data = $restaurant->toArray();

        unset($data['created_at'], $data['updated_at']);

        $response = $this->delete(route('admin.restaurants.destroy', $restaurant), $data);
 
        $this->assertDatabaseHas('restaurants', $data);
        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの一般ユーザーは店舗を削除できない
    public function test_authenticated_regular_user_cannot_delete_restaurant(): void
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::factory()->create();
        $data = $restaurant->toArray();

        unset($data['created_at'], $data['updated_at']);

        $response = $this->actingAs($user)->delete(route('admin.restaurants.destroy', $restaurant), $data);
 
        $this->assertDatabaseHas('restaurants', $data);
        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの管理者は店舗を削除できる
    public function test_authenticated_admin_can_delete_restaurant(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $restaurant = Restaurant::factory()->create();
        $data = $restaurant->toArray();

        $response = $this->actingAs($admin)->delete(route('admin.restaurants.destroy', $restaurant), $data);

        $this->assertDatabaseMissing('restaurants', $data);
        $response->assertStatus(302);
    }

}