import { TableColumn } from './EditableTable3ColumnType';
import { TableConfig } from './EditableTable3ConfigType';
import { TableDataLine } from './EditableTable3DataLineType';
export interface TableParams {
    tableName: string;
    tableConfig: TableConfig;
    dataHeader: TableDataLine;
    indexedColumns: {
        [key: string]: TableColumn;
    };
}
export declare const Caller: {
    ON_CLICK_ADD_AN_ITEM: string;
    ON_CLICK_TRASH_AN_ITEM: string;
    APPLY_SORTABLE_ROW: string;
    ON_SCROLLING: string;
};
//# sourceMappingURL=EditableTable3ParamType.d.ts.map