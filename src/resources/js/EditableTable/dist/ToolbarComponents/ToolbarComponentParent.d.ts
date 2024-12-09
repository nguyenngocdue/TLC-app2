import { LengthAware } from '../Type/EditableTable3DataLineType';
import { TableParams } from '../Type/EditableTable3ParamType';
export declare abstract class ToolbarComponentParent {
    protected params: TableParams;
    protected dataSource: LengthAware;
    constructor(params: TableParams);
    applyPostRenderScript(): void;
    render(): string;
}
//# sourceMappingURL=ToolbarComponentParent.d.ts.map