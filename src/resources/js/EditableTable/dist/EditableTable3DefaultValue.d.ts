import { TableColumn } from './Type/EditableTable3ColumnType';
import { TableParams } from './Type/EditableTable3ParamType';
export declare const ColumnNoValue: TableColumn;
export declare const makeUpDefaultValue: ({ tableName }: TableParams) => TableColumn[];
export declare const convertArrayToLengthAware: (dataSource: any[]) => {
    current_page: number;
    data: any[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: any[];
    next_page_url: any;
    path: string;
    per_page: number;
    prev_page_url: any;
    to: number;
    total: number;
};
export declare const getTooltip: (column: TableColumn) => string;
//# sourceMappingURL=EditableTable3DefaultValue.d.ts.map