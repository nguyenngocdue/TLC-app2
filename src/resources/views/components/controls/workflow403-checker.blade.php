@if(!$allowed)
<x-feedback.alert message="This document is READONLY for your role due to the workflow setup." type="warning"  title="403-Forbidden" />
<br/>
@endif