import { DataFormat } from 'select2';
import { TableColumnDropdown } from '../../Type/EditableTable3ColumnType';
import { Renderer4Edit } from '../Renderer4Edit';
export declare class Dropdown4Edit extends Renderer4Edit {
    protected tableDebug: boolean;
    getOptionsExpensive: (column: TableColumnDropdown) => DataFormat[];
    getOptionsCheap: (column: TableColumnDropdown) => string;
    applyOnChangeScript(): void;
    applyOnMouseMoveScript(): void;
    control(): string;
}
//# sourceMappingURL=Dropdown4Edit.d.ts.map