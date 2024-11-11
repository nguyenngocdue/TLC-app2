import { TableColumn } from "./EditableTable3ColumnType";
import { TableDataLine } from "./EditableTable3DataLineType";

interface TableType  {
    tableName: string,
    columns: TableColumn[];
    dataSource: TableDataLine[];
}

class EditableTable3{
    
    constructor(private params: TableType){
        console.log('EditableTable3', params);
        console.log('EditableTable3', params.columns);
        console.log('EditableTable3', params.dataSource);
    }

    render(){
        const divId = `#${this.params.tableName}`
        const div = document.querySelector(divId)
        div && (div.innerHTML = 'EditableTable3')
        console.log('EditableTable3', divId, div);
    }
}

// Expose EditableTable3 to the global window object
(window as any).EditableTable3 = EditableTable3;