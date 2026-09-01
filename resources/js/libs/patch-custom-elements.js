if (typeof customElements !== 'undefined') {
  const origDefine = customElements.define.bind(customElements);
  customElements.define = function(name, constructor, options) {
    if (customElements.get(name)) {
      return;
    }
    return origDefine(name, constructor, options);
  };
}
