import { TableColumn } from '../Type/EditableTable3ColumnType';
import { TableConfig } from '../Type/EditableTable3ConfigType';
import { TableCellType, TableDataLine, TableRenderedValueObject, TableRendererParams } from '../Type/EditableTable3DataLineType';
import { TableParams } from '../Type/EditableTable3ParamType';
export declare abstract class Renderer4View {
    private params;
    protected tableDebug: boolean;
    protected cellValue: TableCellType;
    protected column: TableColumn;
    protected tableName: string;
    protected dataIndex: number | string;
    protected rowIndex: number;
    protected dataLine: TableDataLine;
    protected controlId: string;
    protected tableConfig: TableConfig;
    protected tableParams: TableParams;
    protected customRenderFn: (() => string) | undefined;
    protected tdClass: string;
    protected tdStyle: {
        [key: string]: string | number;
    };
    protected tdTooltip: string;
    protected divClass: string;
    protected divStyle: {
        [key: string]: string | number;
    };
    protected divTooltip: string;
    constructor(params: TableRendererParams);
    abstract control(): string;
    render(): TableRenderedValueObject;
    applyPostRenderScript(): void;
    applyOnMouseMoveScript(): void;
    applyOnChangeScript(): void;
    protected getTableRendererParams(): TableRendererParams;
}
//# sourceMappingURL=Renderer4View.d.ts.map