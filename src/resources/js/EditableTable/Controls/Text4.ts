import { TableColumn } from '../Type/EditableTable3ColumnType'
import { TableDataLine } from '../Type/EditableTable3DataLineType'
import { TableParams } from '../Type/EditableTable3Type'

export class Text4 {
    constructor(
        private params: TableParams,
        private dataLine: TableDataLine,
        private column: TableColumn,
        private index: number,
    ) {}

    render() {
        return ['Text4 control']
    }
}
