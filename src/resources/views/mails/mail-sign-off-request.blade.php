<x-mail::message>
Hi **{{$receiverName}}**,  
**{{$requesterName}}** sent you a sign-off request:  

+ Project: **{{$projectName}}/{{$subProjectName}}**  
+ Module: **{{$moduleName}}** 
+ Discipline: **{{$disciplineName}}**  
+ Document: **{{$checksheetName}}**  

*Please open this document and sign it off by clicking this button:*
<x-mail::button :url="$url">
Sign off now
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
