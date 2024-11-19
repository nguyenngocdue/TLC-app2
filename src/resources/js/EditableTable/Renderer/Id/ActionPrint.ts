import { TableColumnIdLink } from '../../Type/EditableTable3ColumnType'
import { TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

export class ActionPrint extends Renderer4View {
    render(): TableRenderedValueObject {
        const column = this.column as TableColumnIdLink
        const entityName = column.rendererAttrs?.entityName || '/dashboard/unknown-entity'

        const id = `<i class="fa-duotone fa-print"></i>`
        const href = `${entityName}/${this.cellValue}`

        return {
            rendered: `<a class="text-blue-600 p-2 rounded" href="${href}">${id}</a>`,
            tdClass: `text-center`,
            divClass: `mx-auto`,
            divStyle: { width: '30px' },
        }
    }
}
