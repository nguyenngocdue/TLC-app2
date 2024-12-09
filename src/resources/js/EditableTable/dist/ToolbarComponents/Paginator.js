"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Paginator = void 0;
const ToolbarComponentParent_1 = require("./ToolbarComponentParent");
class Paginator extends ToolbarComponentParent_1.ToolbarComponentParent {
    generateLinks(classList) {
        return this.dataSource.links.map((link) => {
            const iconPrev = `<i class="fas fa-angle-left"></i>`;
            const iconNext = `<i class="fas fa-angle-right"></i>`;
            if (link.url) {
                switch (true) {
                    case link.label.includes('Previous'):
                        return `<span class="${classList}">${iconPrev}</span>`;
                    case link.label.includes('Next'):
                        return `<span class="${classList}">${iconNext}</span>`;
                    default:
                        const disabled = link.active ? 'disabled' : '';
                        return `<a href="${link.url}" class="${classList} ${disabled}">${link.label}</a>`;
                }
            }
            else {
                switch (true) {
                    case link.label === '...':
                        return `.....`;
                    default:
                        return ``;
                }
            }
        });
    }
    render() {
        const { current_page, last_page, first_page_url, last_page_url } = this.dataSource;
        const classList = `focus:shadow-outline-purple rounded border border-r-0 m-0.5 px-0.5 text-white transition-colors duration-150 focus:outline-none border-purple-600 bg-purple-600`;
        // Generate links
        const iconFirst = `<i class="fas fa-angle-double-left"></i>`;
        const iconLast = `<i class="fas fa-angle-double-right"></i>`;
        const links = this.generateLinks(classList);
        let first_page_btn = ``;
        if (current_page > 1)
            first_page_btn = `<a href="${first_page_url}" class="${classList}">${iconFirst}</a>`;
        let last_page_btn = ``;
        if (current_page < last_page)
            last_page_btn = `<a href="${last_page_url}" class="${classList}">${iconLast}</a>`;
        // Build the paginator as a string
        const paginator = `
            <div class="paginator">
                <div class="flex items-baseline my-1">
                    ${first_page_btn}
                    ${links.join('')}
                    ${last_page_btn}
                </div>
            </div>
        `;
        return paginator.trim(); // Trim to remove unnecessary whitespace
    }
}
exports.Paginator = Paginator;
//# sourceMappingURL=Paginator.js.map