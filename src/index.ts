const setStyle = (element: HTMLElement, style: CSSStyleDeclaration) => {
    for (let i = 0; i < style.length; i++) {
        const key = style[i]
        element.style[key] = style[key]
    }
}
const copyStylesToClone = (elA: Element, elB: Element) => {
    const style = window.getComputedStyle(elA, null)
    setStyle(elB as HTMLElement, style)
    for (let i = 0; i < elA.children.length; i++) {
        copyStylesToClone(elA.children[i], elB.children[i])
    }
}
export const printAsSeen = (element: HTMLElement, config: Config) => {

    const data = element.cloneNode(true) as Element
    copyStylesToClone(element, data)

    const iframe = document.createElement('iframe')
    iframe.style.display = 'none'
    document.body.after(iframe)

    const iframeInnerDocument = iframe.contentDocument
    const iframeInnerWindow = iframe.contentWindow
    const iframeInnerStyle = document.createElement('style')

    iframeInnerStyle.innerHTML += config.style || ''

    iframeInnerDocument.body.append(data)
    iframeInnerDocument.head.append(iframeInnerStyle)

    iframeInnerWindow.print()
    setTimeout(() => iframe.remove(), 10000)
}
