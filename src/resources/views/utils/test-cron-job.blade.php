@extends('layouts.app')
@section('topTitle', "Test Cron Job")
@section('title', 'Test Cron Job')

@section('content')

<div class='w-full m-10 min-h-screen'>
    Test Cron Job
    <div class="grid grid-cols-6 gap-4 w-3/4 mx-auto justify-center items-center">
        <x-renderer.button href="?case=start_of_week_timesheet_remind">Start Of Week Timesheet Remind (To Managers)</x-renderer.button>
        <x-renderer.button href="?case=sign_off_remind">Sign Off Remind</x-renderer.button>
        <x-renderer.button href="?case=transfer_diginet_data">Transfer Diginet Data</x-renderer.button>
        <x-renderer.button href="?case=clean_up_trash">Clean Up Trash</x-renderer.button>
    </div>

    Test Function
    <div class="grid grid-cols-6 gap-4 w-3/4 mx-auto justify-center items-center">
        <x-renderer.button href="?case=send_test_mail&email=tchvan@gmail.com">Send A Test Email</x-renderer.button>
        <x-renderer.button href="?case=test_wss">Test WSS</x-renderer.button>
        <x-renderer.button href="?case=test_queue">Test Queue</x-renderer.button>
        <x-renderer.button href="?case=test_email_on_ldap_server">Test Email On LDAP Server</x-renderer.button>
        <x-renderer.button href="?case=refresh_attachment_orphan">Refresh Attachment Orphan</x-renderer.button>
    </div>
</div>

@endsection