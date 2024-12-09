import { LengthAware } from '../Type/EditableTable3DataLineType';
import { TableParams } from '../Type/EditableTable3ParamType';
export declare const lastRenderedStartIdx: {
    [tableName: string]: number;
};
export declare const visibleRowIds: {
    [tableName: string]: Set<string>;
};
export declare const renderOneEmptyRow: (tableParams: TableParams, rowIdx: number, caller: string) => HTMLTableRowElement;
export declare const removeOneRow: (tableParams: TableParams, rowIdx: number) => void;
export declare const renderRows: (data: any[], indices: {
    startIdx: number;
    endIdx: number;
}, tableParams: TableParams, visibleRowCount: number, position: "top" | "bottom") => void;
export declare const updateVisibleRows: (virtualTable: HTMLTableElement, dataSource: LengthAware, tableParams: TableParams, firstLoad?: boolean) => void;
//# sourceMappingURL=updateVirtualTableVisibleRows.d.ts.map