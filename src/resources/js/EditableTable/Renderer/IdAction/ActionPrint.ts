import { TableColumnIdLink } from '../../Type/EditableTable3ColumnType'
import { Renderer4View } from '../Renderer4View'

export class ActionPrint extends Renderer4View {
    protected tdClass: string = `text-center`
    protected divClass: string = `mx-auto`
    protected divStyle: { [key: string]: string | number } = { width: '30px' }

    control() {
        const column = this.column as TableColumnIdLink
        const entityName = column.rendererAttrs?.entityName || '/dashboard/unknown-entity'

        const id = `<i class="fa-duotone fa-print"></i>`
        const href = `${entityName}/${this.cellValue}`

        return `<a class="text-blue-600 p-2 rounded" href="${href}">${id}</a>`
    }
}
