import { TableColumn } from './Type/EditableTable3ColumnType';
import { TableDataLine } from './Type/EditableTable3DataLineType';
import { TableParams } from './Type/EditableTable3ParamType';
export declare const makeTCell: (params: TableParams, dataLine: TableDataLine, column: TableColumn, rowIndex: number) => {
    rendered: any;
    tdClass: string;
    tdStyle: {
        [key: string]: string | number;
    };
    tdTooltip: string;
    divClass: string;
    divStyle: {
        [key: string]: string | number;
    };
    divTooltip: string;
    p_2: boolean;
    componentCase: string;
    applyPostRenderScript: () => void;
    applyOnMouseMoveScript: () => void;
    applyOnChangeScript: () => void;
};
//# sourceMappingURL=EditableTable3TCell.d.ts.map