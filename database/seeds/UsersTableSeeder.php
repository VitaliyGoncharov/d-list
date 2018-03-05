<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = $this->getData();
        DB::table('users')->insert($data);
    }

    public function getData()
    {
        return [
            [
                'surname' => 'Гончаров',
                'name' => 'Виталий',
                'email' => 'vitas1000april@mail.ru',
                'password' => bcrypt(123456),
                'gender' => 'male',
                'birth' => '1998-04-07',
                'avatar' => 'http://www.vseznaika.org/wp-content/uploads/2016/03/pic-00892.jpg',
                'link' => 'id1',
                'active' => 1
            ],
            [
                'surname' => 'Аванесян',
                'name' => 'Арина',
                'email' => 'arina@mail.ru',
                'password' => bcrypt(123456),
                'gender' => 'female',
                'birth' => '1997-01-11',
                'avatar' => 'http://c0.emosurf.com/0001pf0ysXco/0_ef653_1ca0d0f_orig.jpg',
                'link' => 'id2',
                'active' => 1
            ],
            [
                'surname' => 'Аршакян',
                'name' => 'Нарек',
                'email' => 'narek@mail.ru',
                'password' => bcrypt(123456),
                'gender' => 'female',
                'birth' => '1998-04-07',
                'avatar' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRY5LXlPh7vv30VPUPkTCXf4uvTESse0_v_eyF9Zwb1jRn88R0N8A',
                'link' => 'id3',
                'active' => 1
            ],
            [
                'surname' => 'Гречко',
                'name' => 'Никита',
                'email' => 'nikita@mail.ru',
                'password' => bcrypt(123456),
                'gender' => 'male',
                'birth' => '1997-04-16',
                'avatar' => 'https://e-oboi.com/wp-content/uploads/2017/05/shutterstock_46132399.jpg',
                'link' => 'id4',
                'active' => 1
            ],
            [
                'surname' => 'Довгопол',
                'name' => 'Екатерина',
                'email' => 'ekaterina@mail.ru',
                'password' => bcrypt(123456),
                'gender' => 'female',
                'birth' => '1997-04-16',
                'avatar' => 'http://bipbap.ru/wp-content/uploads/2017/04/priroda_kartinki_foto_03.jpg',
                'link' => 'id5',
                'active' => 1
            ],
            [
                'surname' => 'Каминский',
                'name' => 'Виктор',
                'email' => 'viktor@mail.ru',
                'password' => bcrypt(123456),
                'gender' => 'male',
                'birth' => '1997-09-23',
                'avatar' => 'http://s3.travelask.ru/system/images/files/000/336/892/wysiwyg/10452canada-landscape-map-wallpaper-3.jpg?1502197579',
                'link' => 'id6',
                'active' => 1
            ],
            [
                'surname' => 'Золотов',
                'name' => 'Илья',
                'email' => 'ilya@mail.ru',
                'password' => bcrypt(123456),
                'gender' => 'male',
                'birth' => '1997-07-25',
                'avatar' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTNMsQ-x-gbi6Wwzppr4BSpvOYbTqSNc0ay1GDYhoHMzTvt0Gsg',
                'link' => 'id7',
                'active' => 1
            ],
            [
                'surname' => 'Севцова',
                'name' => 'Анастасия',
                'email' => 'anastasia.sevtsova@mail.ru',
                'password' => bcrypt(123456),
                'gender' => 'female',
                'birth' => '1997-02-14',
                'avatar' => 'http://priroda36.ru/images/stories/letters/japan/japan.jpg',
                'link' => 'id8',
                'active' => 1
            ],
            [
                'surname' => 'Селина',
                'name' => 'Дарья',
                'email' => 'darya.selina@mail.ru',
                'password' => bcrypt(123456),
                'gender' => 'female',
                'birth' => '1997-02-14',
                'avatar' => 'https://i.ytimg.com/vi/3QV6wStbXQk/maxresdefault.jpg',
                'link' => 'id9',
                'active' => 1
            ],
            [
                'surname' => 'Муренко',
                'name' => 'Виолетта',
                'email' => 'veta@mail.ru',
                'password' => bcrypt(123456),
                'gender' => 'female',
                'birth' => '1997-06-24',
                'avatar' => 'http://mos-holidays.ru/wp-content/uploads/2017/03/vyistavka-priroda-v-polutonah-1.jpg',
                'link' => 'id10',
                'active' => 1
            ],
            [
                'surname' => 'Тимофеева',
                'name' => 'Александра',
                'email' => 'aleksandra@mail.ru',
                'password' => bcrypt(123456),
                'gender' => 'female',
                'birth' => '1998-03-12',
                'avatar' => 'https://natworld.info/wp-content/uploads/2017/04/%D0%BF%D1%80%D0%B8%D1%80%D0%BE%D0%B4%D0%B0-%D0%BC%D0%B8%D1%80%D0%B0.jpg',
                'link' => 'id11',
                'active' => 1
            ],
            [
                'surname' => 'Пустовалова',
                'name' => 'Юлия',
                'email' => 'yulia@mail.ru',
                'password' => bcrypt(123456),
                'gender' => 'female',
                'birth' => '1997-12-19',
                'avatar' => 'http://vmeste-rastem.ru/wp-content/uploads/2016/07/priroda-3.jpg',
                'link' => 'id12',
                'active' => 1
            ],
            [
                'surname' => 'Юлин',
                'name' => 'Матвей',
                'email' => 'matvey@mail.ru',
                'password' => bcrypt(123456),
                'gender' => 'male',
                'birth' => '1997-09-03',
                'avatar' => 'https://cdn.fishki.net/upload/post/2016/09/06/2065949/tn/priroda-sahalina-yuriy-podsevalov0001.jpg',
                'link' => 'id13',
                'active' => 1
            ]
        ];
    }
}
