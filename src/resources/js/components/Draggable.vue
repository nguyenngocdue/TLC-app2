<template>
    <Draggable>
        <slot></slot>
    </Draggable>
</template>
<script>
import { Draggable as Dra } from '@fullcalendar/interaction'
export default {
    components: {
        Draggable,
    },
    data() {
        return {}
    },
}
</script>

<style></style>
