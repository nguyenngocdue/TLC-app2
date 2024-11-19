import { TableRenderedValueObject } from '../Type/EditableTable3DataLineType'
import { Renderer4View } from './Renderer4View'

export abstract class Renderer4Edit extends Renderer4View {
    abstract control(): string

    render(): TableRenderedValueObject {
        return {
            rendered: this.control(),
        }
    }
}
