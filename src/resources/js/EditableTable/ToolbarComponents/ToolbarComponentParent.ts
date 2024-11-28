import { LengthAware } from '../Type/EditableTable3DataLineType'
import { TableParams } from '../Type/EditableTable3ParamType'

declare let tableData: { [tableName: string]: LengthAware }
export abstract class ToolbarComponentParent {
    protected params: TableParams
    protected lengthAware: LengthAware
    constructor(params: TableParams) {
        this.params = params
        this.lengthAware = tableData[params.tableName] as LengthAware
    }
    applyPostRenderScript() {}
    render() {
        return ''
    }
}
