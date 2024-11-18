import { TbodyTr } from './EditableTable3TBodyTRow'
import { TableParams } from './Type/EditableTable3ParamType'

export class TbodyTrs {
    constructor(private params: TableParams) {}

    render() {
        const { dataSource } = this.params
        if (!dataSource.data) return []

        const result = dataSource.data.map((row, rowIndex) =>
            new TbodyTr(this.params, row, rowIndex).render(),
        )
        return result
    }
}
