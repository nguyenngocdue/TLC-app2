<x-mail::message>
Hi **{{$receiverName}}**,  
**{{$requesterName}}** has cancelled a sign-off request:  

+ Project: **{{$projectName}}/{{$subProjectName}}**  
+ Module: **{{$moduleName}}** 
+ Discipline: **{{$disciplineName}}**  
+ Checksheet: **{{$checksheetName}}**  

*You don't need to sign off on the above request anymore.*
{{-- <x-mail::button :url="$url">
Sign off now
</x-mail::button> --}}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
