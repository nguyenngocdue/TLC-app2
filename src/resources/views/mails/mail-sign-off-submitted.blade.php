<x-mail::message>
Hi **{{$monitorNames}}**,  
**{{$inspectorName}}** has made a sign-off decision:

+ Project: **{{$projectName}}/{{$subProjectName}}**  
+ Module: **{{$moduleName}}** 
+ Discipline: **{{$disciplineName}}**  
+ Checksheet: **{{$checksheetName}}**  

<x-mail::button :url="$url">
Open the Check Sheet
</x-mail::button>

+ Inspector Decision: <x-mail::status>{{$signature_decision}}</x-mail::status>
+ Inspector Comment: **{{$signature_comment ?? "(none)"}}**  

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
