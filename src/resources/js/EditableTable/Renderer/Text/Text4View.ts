import { Renderer4View } from '../Renderer4View'

export class Text4View extends Renderer4View {
    protected tdClass: string = `whitespace-nowrap`
    protected divClass: string = `w-40 truncate`

    control() {
        const rendered = this.cellValue as unknown as string
        return rendered
    }
}
