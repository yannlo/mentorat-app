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
    this.count = this.element.querySelector("[data-container]")
      ? Array.from(
          this.element.querySelector("[data-container]").children
        ).filter((child) => child.tagName === "DIV").length
      : 0;
    this.index = this.count + 1;
    this.limit = this.element.dataset.limit || null;

    const btns = Array.prototype.slice.call(
      this.element.querySelectorAll("[data-action-add]")
    );
    this.btnAdd = btns.filter(
      (v) =>
        v.parentElement == this.element ||
        v.parentElement.parentElement == this.element
    )[0];

    const btnRemoves = this.element.querySelectorAll("[data-action-remove]");

    this.btnAdd.addEventListener("click", this.addElement);
    btnRemoves.forEach((elt) =>
      elt.addEventListener("click", this.removeElement)
    );
  }
  /**
   *
   * @param {MouseEvent} event
   */
  /**
   * Handles the addition of a new form element to a collection.
   * 
   * - Prevents the default event behavior.
   * - Clones a prototype template, replacing `__name__` with the current index.
   * - Attaches a remove event listener to the new element.
   * - Animates the appearance of the new element.
   * - Increments the internal index and count.
   * - Disables the add button if a limit is reached.
   * 
   * @param {Event} event - The event triggered by the add action.
   * @returns {Element|undefined} The newly added element, or undefined if not added.
   */
  addElement = (event) => {
    event.preventDefault();

    const prototypeTemplate = this.element.querySelector("#prototype");
    if (!prototypeTemplate) return;

    const prototypes = Array.prototype.slice.call(
      this.element.parentElement.querySelectorAll("#prototype")
    );
    const prototype = prototypes.filter(
      (v) => v.parentElement == this.element
    )[0];

    const element = document
      .createRange()
      .createContextualFragment(
        prototype?.innerHTML.replaceAll("__name__", this.index)
      ).firstElementChild;

    element
      .querySelector("[data-action-remove]")
      ?.addEventListener("click", this.removeElement);

    // Prepare for animation: set initial state
    element.style.opacity = "0";
    element.style.transform = "scale(0.97)";
    element.style.transition =
      "opacity 0.35s cubic-bezier(0.4,0,0.2,1), transform 0.35s cubic-bezier(0.4,0,0.2,1)";

    this.element.querySelector("[data-container]")?.appendChild(element);

    this.index++;
    this.count++;

    // Make new element visible, then animate
    this.makeNewEltVisible(element);

    // Start animation after element is visible
    setTimeout(() => {
      element.style.opacity = "1";
      element.style.transform = "scale(1)";
      // Clean up transition after animation
      setTimeout(() => {
        element.style.transition = "";
      }, 400);
    }, 50);

    if (this.limit !== null && this.count >= this.limit) {
      event.currentTarget.setAttribute("disabled", "true");
      return;
    }
    return element;
  };

  /**
   *
   * @param {MouseEvent} event
   */
  removeElement = (event) => {
    event.preventDefault();

    if (this.count == 0) {
      return;
    }

    const elt = event.currentTarget.parentElement;
    console.log(elt, this.element);

    // Animate removal
    elt.style.transition =
      "opacity 0.35s cubic-bezier(0.4,0,0.2,1), transform 0.35s cubic-bezier(0.4,0,0.2,1)";
    elt.style.opacity = "0";
    elt.style.transform = "scale(0.97)";
    setTimeout(() => {
      elt.remove();
    }, 350);

    this.count--;

    if (this.limit !== null && this.count < this.limit) {
      this.btnAdd.removeAttribute("disabled");
    }
  };

  /**
   * Ensures the given DOM element is visible within the viewport.
   * If the element is not fully visible, it scrolls the element into view smoothly and centers it vertically.
   *
   * @param {HTMLElement} element - The DOM element to make visible.
   */
  makeNewEltVisible = (element) => {
    if (element) {
      const rect = element.getBoundingClientRect();
      const isVisible =
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <=
          (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <=
          (window.innerWidth || document.documentElement.clientWidth);

      if (!isVisible) {
        element.scrollIntoView({ behavior: "smooth", block: "center" });
      }
    }
  };
}
