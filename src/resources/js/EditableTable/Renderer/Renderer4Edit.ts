import { Renderer4View } from './Renderer4View'

export abstract class Renderer4Edit extends Renderer4View {
    abstract control(): string

    render() {
        return {
            rendered: this.control(),
            classStr: this.column.classList || '',
        }
    }
}
