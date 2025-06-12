import { Controller } from "@hotwired/stimulus";

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
  connect() {
    /**
     * @var {HTMLElement} elementchildNodes
     */
    this.index = this.element.childEllengthementCount || 1;

    const btn = document.createElement("button");
    // add a class to outline button with tailwind
    btn.classList.add(
      "border-2",
      "border-indigo-600",
      "rounded",
      "px-4",
      "py-2",
      "bg-white",
      "text-indigo-600",
      "hover:bg-indigo-100"
    );

    btn.innerText = "Ajouter";
    btn.setAttribute("type", "button");
    btn.addEventListener("click", this.addElement);
    this.element.childNodes.forEach((element) => {
      if (element.nodeType !== Node.ELEMENT_NODE) return;
      this.addDeleteSection(element);
    });
    this.element.appendChild(btn);
  }
  /**
   *
   * @param {MouseEvent} event
   */
  addElement = (event) => {
    event.preventDefault();

    const element = document
      .createRange()
      .createContextualFragment(
        this.element.dataset["prototype"].replaceAll("__name__", this.index)
      ).firstElementChild;

    this.addDeleteSection(element);
    this.index++;
    event.currentTarget.insertAdjacentElement("beforebegin", element);
  };

  /**
   *
   * @param {HTMLElement} item
   */
  addDeleteSection = (item) => {
    const btn = document.createElement("button");
    const container = document.createElement("div");

    // add a class to outline button with tailwind
    btn.classList.add(
      "border-2",
      "border-red-600",
      "rounded",
      "px-4",
      "py-2",
      "bg-white",
      "text-red-600",
      "hover:bg-red-100"
    );
    container.classList.add(
      "flex", 
      "justify-end",
      "mb-4",
      "px-8"
    );

    if (item.firstElementChild.nodeName === "LABEL") {
      // If the first child is a label, we need to move it to the end of the item
      const label = item.firstElementChild;
      label.classList.remove("mb-2","text-sm", "text-gray-600");
      container.appendChild(label);
      container.classList.replace("justify-end", "justify-between");
      container.classList.add("w-full", "items-end");
    }

    btn.innerText = "Supprimer";
    btn.setAttribute("type", "button");
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      item.remove();
    });
    
    container.appendChild(btn);
    item.prepend(container);
  };
}
