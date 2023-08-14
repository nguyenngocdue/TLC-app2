<template>
    <a-result :status="code" :title="code" :sub-title="message">
        <template #extra>
            <a-button type="primary" @click="refreshPage">Refresh</a-button>
            <!-- <a-button type="primary" @click="goBack">Back</a-button> -->
            <a-button type="primary" href='/'>Home</a-button>
            <a-button type="primary" href='/login'>Login</a-button>
            <a-button v-if="url" type="primary" :href="url">View All</a-button>
        </template>
    </a-result>
</template>
<script>
export default {
    props: ['code', 'message','url'],
    methods: {
        refreshPage() {
            window.location.reload();
        },
        // goBack() {
        //     window.history.back();
        // }
    }
}
</script>

