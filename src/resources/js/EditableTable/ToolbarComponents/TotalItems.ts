import { Str } from '../Functions'
import { LengthAware } from '../Type/EditableTable3DataLineType'
import { TableParams } from '../Type/EditableTable3ParamType'

declare let tableData: { [tableName: string]: LengthAware }

export class TotalItems {
    private lengthAware: LengthAware
    constructor(params: TableParams) {
        const tableName = params.tableName
        const dataSource = tableData[tableName]
        this.lengthAware = dataSource as LengthAware
        // console.log(this.lengthAware)
    }

    render() {
        const { total } = this.lengthAware
        const totalStr = Str.humanReadable(total)
        const itemStr = total === 1 ? 'item' : 'items'
        return `Total <span class="font-bold px-0.5" title="${total}">${totalStr}</span> ${itemStr}`
    }
}
