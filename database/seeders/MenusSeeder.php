<?php

namespace Database\Seeders;

use App\Models\MenusModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        date_default_timezone_set('Asia/Jakarta');
        $json = file_get_contents(public_path('json/menu.json'));
        $menus = json_decode($json, true);
        foreach ($menus as  $menu) {
            $root = MenusModel::create(collect($menu)->except('childrens')->toArray());
            $this->insertChildrens($menu ,$root);
        }
    }

    public function insertChildrens(array $menuData ,MenusModel $rootMenu)
    {
        if (!empty($menuData["childrens"])) {
            foreach ($menuData['childrens'] as $children) {
                $children["parent_id"] = $rootMenu->id;
                $res = MenusModel::create(collect($children)->except('childrens')->toArray());
                if ($children["childrens"]) $this->insertChildrens($children, $res);
            }
        }
    }
}
