import { Str } from '../../Functions'
import { TableColumnIdLink } from '../../Type/EditableTable3ColumnType'
import { Renderer4View } from '../Renderer4View'

export class IdLink extends Renderer4View {
    protected tdClass: string = `text-center`
    protected divStyle: { [key: string]: string | number } = { width: '70px' }

    control() {
        const column = this.column as TableColumnIdLink
        // console.log(column.rendererAttrs)
        const entityName = column.rendererAttrs?.entityName || '/dashboard/unknown-entity'

        const rendered01 = this.cellValue as unknown as number
        const rendered02 = this.cellValue as unknown as string

        let id = 'cell-value-is-empty'
        switch (true) {
            case rendered01 !== undefined:
                id = Str.makeId(rendered01)
                break
            case rendered02 !== undefined:
                id = rendered02
                break
        }
        const href = `${entityName}/${this.cellValue}/edit`

        return `<a class="text-blue-600 hover:bg-blue-600 hover:text-white p-2 rounded" href="${href}">${id}</a>`
    }
}
