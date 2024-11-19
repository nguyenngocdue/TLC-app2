import { Str } from '../../Functions'
import { TableColumnIdLink } from '../../Type/EditableTable3ColumnType'
import { TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

export class IdLink extends Renderer4View {
    render(): TableRenderedValueObject {
        const column = this.column as TableColumnIdLink
        const entityName = column.rendererAttrs?.entityName || '/dashboard/unknown-entity'

        const rendered01 = this.cellValue as unknown as number
        const rendered02 = this.cellValue as unknown as string

        const id = Str.makeId(rendered01 || rendered02)
        const href = `${entityName}/${this.cellValue}/edit`

        return {
            rendered: `<a class="text-blue-600 hover:bg-blue-600 hover:text-white p-2 rounded" href="${href}">${id}</a>`,
            tdClass: `text-center`,
            divStyle: { width: '70px' },
        }
    }
}
