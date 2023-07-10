<template>
    <a-result :status="code" :title="code" :sub-title="message">
        <template #extra>
            <a-button type="primary" href="/">Back Home</a-button>
        </template>
    </a-result>
</template>
<script>
    export default {
        props: ['code','message'],
    }
</script>

