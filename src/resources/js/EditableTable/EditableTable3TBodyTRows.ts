import { TbodyTr } from './EditableTable3TBodyTRow'
import { LengthAware } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'

declare let tableData: { [tableName: string]: LengthAware }

export class TbodyTrs {
    constructor(private params: TableParams) {}

    render() {
        // const { dataSource } = this.params
        const dataSource = tableData[this.params.tableName]
        if (!dataSource.data) return []

        const result = dataSource.data.map((_, rowIndex) =>
            new TbodyTr(this.params, rowIndex).render(),
        )
        return result
    }
}
