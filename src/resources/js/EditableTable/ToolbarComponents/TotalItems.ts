import { ToolbarComponentParent } from './ToolbarComponentParent'
import { Str } from '../Functions'

export class TotalItems extends ToolbarComponentParent {
    render() {
        const { total } = this.lengthAware
        const totalStr = Str.humanReadable(total)
        const itemStr = total === 1 ? 'item' : 'items'
        return `Total <span class="font-bold px-0.5" title="${total}">${totalStr}</span> ${itemStr}`
    }
}
