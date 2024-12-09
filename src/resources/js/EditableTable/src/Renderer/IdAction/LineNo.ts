import { Renderer4View } from '../Renderer4View'

export class LineNo extends Renderer4View {
    protected tdClass: string = `text-center`
    control() {
        const rowIndex = this.rowIndex
        const rendered = (rowIndex + 1).toString()
        this.divClass = this.tableConfig.orderable ? `drag-handle cursor-grab` : ``

        return rendered
    }
}
