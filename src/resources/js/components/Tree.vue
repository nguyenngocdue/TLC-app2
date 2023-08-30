<template>
    <div>
        <a-tree
        :default-expanded-keys="['0-0-0']"
        :tree-data="treeData"
        @select="onSelect"
        >
        </a-tree>
    </div>
</template>
<script>
export default {
    props: ['treeData'],
    methods: {
        onSelect(){
            return (selectedKeys, info) => {
                console.log('selected', selectedKeys, info);
            };
        } 
    },
}

</script>

