import { Renderer4View } from '../Renderer4View'

export class Boolean4View extends Renderer4View {
    protected tdClass: string = `text-center`
    control() {
        const { cellValue } = this
        const value = cellValue ? `<i class="fas fa-circle-check text-green-500 text-lg"></i>` : ``
        return value
    }
}
