<?php

namespace App\Http\Controllers\Utils\TestFunction;

use App\Models\User;
use LdapRecord\Laravel\LdapUserRepository;
use LdapRecord\Models\ActiveDirectory\User as LdapUser0;

class TestEmailOnLdapServer
{
    private static function testByField($fieldToCheck, $usersOnLdap, $emails)
    {
        dump("Field to check: " . $fieldToCheck);
        $emailFound = collect();
        foreach ($usersOnLdap as $user) {
            $tmp = ($user->{$fieldToCheck});
            if ($tmp) $emailFound->push(...$tmp);
        }

        $emailFound = $emailFound->sort()->values()->map(fn ($name) => strtolower($name));
        dump($emailFound);
        dump($emails->diff($emailFound));
        echo "<br/>";
    }

    public static function Test()
    {
        $emails = User::query()
            ->where('email', 'LIKE', '%@tlcmodular.com')
            ->whereNot('resigned', true)
            ->pluck('email')
            ->map(fn ($name) => strtolower($name));

        $usersOnLdap = (new LdapUserRepository(LdapUser0::class))->query()->get();

        static::testByField("userprincipalname", $usersOnLdap, $emails);
        static::testByField("mail", $usersOnLdap, $emails);
    }
}
