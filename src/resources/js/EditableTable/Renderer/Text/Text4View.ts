import { TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

export class Text4View extends Renderer4View {
    render(): TableRenderedValueObject {
        const rendered = this.cellValue as unknown as string
        return {
            rendered,
            tdClass: `whitespace-nowrap`,
            divClass: `w-40 truncate`,
        }
    }
}
