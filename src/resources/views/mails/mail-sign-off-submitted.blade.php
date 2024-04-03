<x-mail::message>
Hi **{{$monitorNames}}**,  
**{{$inspectorName}}** has made a sign-off decision:

+ Project: **{{$projectName}}/{{$subProjectName}}**  
+ Module: **{{$moduleName}}** 
+ Discipline: **{{$disciplineName}}**  
+ Document: **{{$checksheetName}}**  

<x-mail::button :url="$url">
Open the Document
</x-mail::button>

+ Decision: <x-mail::status>{{$signature_decision}}</x-mail::status>
+ Comment: **{{$signature_comment ?? "(none)"}}**  

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
