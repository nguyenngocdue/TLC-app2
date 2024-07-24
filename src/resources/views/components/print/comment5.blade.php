<div>
    <x-renderer.table 
        tableName='table_01' 
        showNo={{false}} 
        :columns="$tableEditableColumns" 
        :dataSource="$tableDataSource" 
        />
</div>