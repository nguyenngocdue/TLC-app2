import { TableColumn } from './EditableTable3ColumnType'
import { TableConfig } from './EditableTable3ConfigType'
import { TableDataLine } from './EditableTable3DataLineType'

export interface TableParams {
    tableName: string
    tableConfig: TableConfig
    // columns: TableColumn[]
    // dataSource: LengthAware
    dataHeader: TableDataLine

    //derived from columns
    indexedColumns: { [key: string]: TableColumn }
}

export const Caller = {
    ON_CLICK_ADD_AN_ITEM: 'onClickAddAnItem',
    ON_CLICK_TRASH_AN_ITEM: 'onClickTrashAnItem',
    APPLY_SORTABLE_ROW: 'applySortableRow',
    ON_SCROLLING: 'onScrolling',
}
