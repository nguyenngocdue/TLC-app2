@if(!$allowed)
<x-feedback.alert message="This document is in READONLY mode." type="warning"  title="403" />
<br/>
@endif