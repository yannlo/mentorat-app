import { Controller } from "@hotwired/stimulus";
import formCollectionController from "./form-collection_controller";
/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */

/**
 * An array of objects representing the days of the week.
 * Each object contains:
 *  - value: The English name of the day (string, lowercase).
 *  - label: The French name of the day (string, capitalized).
 *  - elt: A placeholder for an associated DOM element (initially null).
 *
 * @type {Array<{ value: string, label: string, elt: HTMLDivElement | null }>}
 */
const DAYS = [
  { value: "monday", label: "Lundi", elt: null },
  { value: "tuesday", label: "Mardi", elt: null },
  { value: "wednesday", label: "Mercredi", elt: null },
  { value: "thursday", label: "Jeudi", elt: null },
  { value: "friday", label: "Vendredi", elt: null },
  { value: "saturday", label: "Samedi", elt: null },
  { value: "sunday", label: "Dimanche", elt: null },
];

export default class extends formCollectionController {
  connect() {
    super.connect();

    this.verifyDayWithElt();
    this.deleteSelectedOption();

    this.getDaysForm().forEach((f) => {
      f.querySelector("select")?.addEventListener("change", (e) => {
        this.onChange(e, f);
      });
    });
  }


  /**
   * Retrieves all child nodes of the element with the `[data-container]` attribute
   * that are instances of `HTMLDivElement`.
   *
   * @returns {HTMLDivElement[]} An array of div elements found within the data container.
   */
  getDaysForm = () => {
    const nodes = Array.prototype.slice.call(
      this.element.querySelector("[data-container]").childNodes
    );
    return nodes.filter((node) => node instanceof HTMLDivElement);
  };

  verifyDayWithElt = () => {
    // Reset all elt references
    DAYS.forEach((d) => (d.elt = null));

    // Map selected values to their corresponding form elements
    this.getDaysForm().forEach((formElt) => {
      const value = formElt.querySelector("select")?.value;
      const day = DAYS.find((d) => d.value === value);
      if (day) day.elt = formElt;
    });
  };

  deleteSelectedOption = () => {
    // Collect all selected day values
    const selectedValues = this.getDaysForm()
      .map((form) => form.querySelector("select")?.value)
      .filter(Boolean);

    // For each form, remove options that are selected in other forms
    this.getDaysForm().forEach((form) => {
      const select = form.querySelector("select");
      if (!select) return;
      const currentValue = select.value;
      Array.from(select.options).forEach((option) => {
        if (
          option.value !== currentValue &&
          selectedValues.includes(option.value)
        ) {
          option.remove();
        }
      });
    });
  };

  onChange = (e, dayFormAssociated) => {
    e.preventDefault();
    this.verifyDayWithElt();
    this.restSelectOptions();
    this.deleteSelectedOption();
    this.sortDaysForm(dayFormAssociated);

    // Always center the updated dayFormAssociated in the viewport
    dayFormAssociated.scrollIntoView({
      behavior: "smooth",
      block: "center",
      inline: "center",
    });
  };

  restSelectOptions = () => {
    this.getDaysForm().forEach((form) => {
      const select = form.querySelector("select");
      const existingValues = Array.from(select.children).map(
        (opt) => opt.value
      );

      DAYS.forEach((day, idx) => {
        if (!existingValues.includes(day.value)) {
          const option = document.createElement("option");
          option.value = day.value;
          option.textContent = day.label;

          // Find the correct position to insert
          let insertBefore = null;
          for (let i = 0; i < select.children.length; i++) {
            const childIdx = DAYS.findIndex(
              (d) => d.value === select.children[i].value
            );
            if (childIdx > idx) {
              insertBefore = select.children[i];
              break;
            }
          }
          select.insertBefore(option, insertBefore);
        }
      });
    });
  };

  /**
   * Sorts the day forms inside the [data-container] by the order of DAYS and animates the transition,
   * keeping the dayFormAssociated centered in the viewport during the animation.
   * @param {HTMLDivElement} [dayFormAssociated] - The form to center during and after sorting (optional).
   */
  sortDaysForm = (dayFormAssociated) => {
    const container = this.element.querySelector("[data-container]");
    if (!container) return;

    const forms = this.getDaysForm();
    // Sort forms by the index of their select value in DAYS
    const sortedForms = [...forms].sort((a, b) => {
      const aValue = a.querySelector("select")?.value;
      const bValue = b.querySelector("select")?.value;
      const aIdx = DAYS.findIndex((d) => d.value === aValue);
      const bIdx = DAYS.findIndex((d) => d.value === bValue);
      return aIdx - bIdx;
    });

    // Determine which form to center after sorting
    const targetForm =
      dayFormAssociated || document.activeElement?.closest("div");

    // Animation logic extracted to a helper
    this.animateSortAndCenter(container, forms, sortedForms, targetForm, 1000);
  };

  /**
   * Animates sorting of forms and keeps the target form centered during the animation.
   * The targetForm (the one being moved) is placed on top (z-index) during the animation.
   * @param {HTMLElement} container
   * @param {HTMLDivElement[]} currentForms
   * @param {HTMLDivElement[]} sortedForms
   * @param {HTMLDivElement} targetForm
   * @param {number} duration
   */
  animateSortAndCenter = (
    container,
    currentForms,
    sortedForms,
    targetForm,
    duration = 800
  ) => {
    // Center the target form before animation starts
    if (targetForm && container.contains(targetForm)) {
      targetForm.scrollIntoView({
        behavior: "auto",
        block: "center",
        inline: "center",
      });
    }

    // Record initial positions
    const initialRects = new Map();
    currentForms.forEach((form) => {
      initialRects.set(form, form.getBoundingClientRect());
    });

    // Move DOM nodes to sorted order (visually unchanged due to transform)
    sortedForms.forEach((form) => container.appendChild(form));

    // Force reflow
    void container.offsetWidth;

    // Record new positions
    const finalRects = new Map();
    sortedForms.forEach((form) => {
      finalRects.set(form, form.getBoundingClientRect());
    });

    // Move forms back to their original position visually
    sortedForms.forEach((form) => {
      const initial = initialRects.get(form);
      const final = finalRects.get(form);
      const dx = initial.left - final.left;
      const dy = initial.top - final.top;
      form.style.transition = "none";
      form.style.transform = `translate(${dx}px, ${dy}px)`;
      // Bring the targetForm to front during animation
      if (form === targetForm) {
        form.style.zIndex = "10";
      } else {
        form.style.zIndex = "1";
      }
    });

    // Force reflow again
    void container.offsetWidth;

    // Animate to their new position
    sortedForms.forEach((form) => {
      form.style.transition = `transform ${duration}ms cubic-bezier(.4,1.5,.6,1), opacity ${duration}ms`;
      form.style.transform = "translate(0, 0)";
      form.style.opacity = "1";
    });

    // Keep the target centered during animation
    let animationFrame;
    const keepCentered = () => {
      if (targetForm && container.contains(targetForm)) {
        targetForm.scrollIntoView({
          behavior: "auto",
          block: "center",
          inline: "center",
        });
        animationFrame = requestAnimationFrame(keepCentered);
      }
    };
    animationFrame = requestAnimationFrame(keepCentered);

    // Clean up after animation
    setTimeout(() => {
      if (animationFrame) cancelAnimationFrame(animationFrame);
      sortedForms.forEach((form) => {
        form.style.transition = "";
        form.style.transform = "";
        form.style.opacity = "";
        form.style.zIndex = "";
      });
      // Center the target form after animation
      if (targetForm && container.contains(targetForm)) {
        targetForm.scrollIntoView({
          behavior: "smooth",
          block: "center",
          inline: "center",
        });
      }
    }, duration + 50);
  };
}
