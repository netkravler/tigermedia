const elFactory = (e, t, ...a) => {
        const n = document.createElement(e);
        for (key in t) n.setAttribute(key, t[key]);
        return a.forEach(e => {
            "string" == typeof e ? n.appendChild(document.createTextNode(e)) : n.appendChild(e)
        }), n
    },
    change_contact_label = [".contacts", "add_contact_button", "Angiv dine kontaktinfo her", "Angiv endnu en kontakt"],
    parent_list = [{
        icon: "fa fa-user",
        name: "firstname",
        placeholder: "Fornavn",
        required: "required",
        "data-type": "nonum_text",
        invalid: "Navn er påkrævet",
        wrong: "Brug kun bogstaver og mellemrum"
    }, {
        icon: "fa fa-user",
        name: "lastname",
        placeholder: "Efternavn",
        required: "required",
        "data-type": "nonum_text",
        invalid: "Efternavn er påkrævet",
        wrong: "Brug kun bogstaver og mellemrum"
    }, {
        icon: "fa fa-address-card",
        name: "address",
        placeholder: "Adresse",
        required: "required",
        "data-type": "let_num_space",
        invalid: "Adresse er påkrævet",
        wrong: "Kun bogstaver, tal og mellemrum tilladt"
    }, {
        icon: "fa fa-map-pin",
        name: "postalcode",
        placeholder: "Postnummer",
        required: "required",
        "data-type": "nordjylland",
        invalid: "Postnummer er påkrævet",
        wrong: "Ikke et nordjysk postnummer",
        script: {
            event: "onchange",
            target: "city",
            script: "ziplistener"
        }
    }, {
        icon: "fa fa-building",
        name: "city",
        placeholder: "By",
        required: "required",
        "data-type": "let_num_space",
        invalid: "By er påkrævet",
        wrong: "Kun bogstaver, tal og mellemrum tilladt"
    }, {
        icon: "fa fa-phone-square",
        name: "phonenumber",
        placeholder: "Telefonnummer",
        required: "required",
        "data-type": "telefonnummer",
        invalid: "Telefonnummer er påkrævet",
        wrong: "Forkert format på telefonnummer"
    }, {
        icon: "fa fa-envelope-square",
        name: "email",
        placeholder: "E-mail adresse",
        required: "required",
        "data-type": "email",
        invalid: "E-mail adresse er påkrævet",
        wrong: "Forkert format af e-mail"
    }],
    contact_list = [{
        icon: "fa fa-user",
        type: "text",
        class: "field-container contacts__name",
        name: "contact_name",
        placeholder: "Navn",
        required: "required",
        "data-type": "nonum_text",
        invalid: "Et navn er påkrævet",
        wrong: "Brug kun bogstaver og mellemrum"
    }, {
        icon: "fa fa-briefcase",
        type: "text",
        class: "field-container contacts__title",
        name: "contact_title",
        placeholder: "Jobtitel",
        required: "required",
        "data-type": "nonum_text",
        invalid: "En jobtitle er påkrævet",
        wrong: "Brug kun bogstaver og mellemrum"
    }, {
        icon: "fa fa-envelope-square",
        type: "text",
        class: "field-container contacts__email",
        name: "contact_email",
        placeholder: "E-mail adresse",
        required: "required",
        "data-type": "email",
        invalid: "En e-mail er påkrævet",
        wrong: "Ikke korrekt e-mail format"
    }, {
        icon: "fa fa-phone-square",
        type: "text",
        class: "field-container contacts__phonenumber",
        name: "contact_phonenumber",
        placeholder: "Telefonnummer",
        required: "required",
        "data-type": "phonenumber",
        invalid: "Telefonnummer er påkrævet",
        wrong: "Ikke korrekt telefonnummer format"
    }],
    child_list = [{
        icon: "fa fa-calendar",
        type: "date",
        class: "field-container",
        name: "age",
        placeholder: "Navn",
        required: "required",
        "data-type": "nonum_text",
        invalid: "Et navn er påkrævet",
        wrong: "Brug kun bogstaver og mellemrum"
    }, {
        type: "radio",
        class: "field-container",
        id: "gender_boy",
        name: "gender",
        placeholder: "Navn",
        "data-type": "checkbox",
        wrong: "Angiv køn "
    }, {
        type: "radio",
        class: "field-container",
        id: "gender_girl",
        name: "gender",
        placeholder: "Navn",
        "data-type": "checkbox",
        wrong: "Angiv køn "
    }, {
        icon: "fa fa-user",
        type: "text",
        class: "field-container",
        name: "name",
        placeholder: "Navn",
        required: "required",
        "data-type": "nonum_text",
        invalid: "Navn er påkrævet"
    }],
    child_array = [1],
    contact_array = [];
if (document.body.contains(document.getElementById("childrens"))) {
    document.getElementById("childrens");
    document.getElementById("kids_arr").value = child_array
}
if (document.body.contains(document.getElementById("applicants"))) {
    function is_single(e) {
        document.getElementById("card2").innerHTML = "", document.getElementById("card2").classList.add("hidden"), document.getElementById("card3").classList.remove("hidden"), document.body.contains(document.getElementById("legend1")) || choose_spouse(1, "Ansøger")
    }

    function is_hooked(e) {
        document.getElementById("card2").classList.remove("hidden"), document.getElementById("card3").classList.add("hidden"), document.getElementById("card2").innerHTML = "", choose_spouse(2, e)
    }

    function choose_spouse(e, t) {
        let n = document.getElementById("card" + e),
            r = "gift_med" == t ? "Ægtefælle" : t;
        for (n.appendChild(elFactory("legend", {
                id: "legend" + e
            }, r)), a = 0; a < parent_list.length; a++) n.appendChild(elFactory("div", {
            class: "field-container"
        }, elFactory("label", {
            for: parent_list[a].name + e
        }, parent_list[a].placeholder), elFactory("div", {
            class: "icon_container"
        }, elFactory("input", {
            type: "text",
            name: parent_list[a].name + e,
            id: parent_list[a].name + e,
            placeholder: parent_list[a].placeholder,
            required: parent_list[a].required,
            "data-type": parent_list[a]["data-type"],
            class: "field-input",
            onchange: parent_list[a].script ? 'ziplistener(this.value, "' + parent_list[a].script.target + e + '")' : null
        }), elFactory("i", {
            class: parent_list[a].icon
        })), elFactory("small", {
            class: "field-msg error",
            "data-error": "invalid" + capitalizeFirstLetter(parent_list[a].name) + e
        }, parent_list[a].invalid), elFactory("small", {
            class: "field-msg_wrong error",
            "data-error": "wrongformat" + capitalizeFirstLetter(parent_list[a].name) + e
        }, parent_list[a].wrong)));
        let l = ["Vælg Indkomst grundlag", "SU", "Kontanthjælp", "Dagpenge", "Ressourceforløb", "Førtidspension", "Flexjob", "Job"],
            i = document.createElement("select");
        i.setAttribute("required", "required"), i.setAttribute("id", "jobselect" + e), i.setAttribute("name", "jobselect" + e), i.setAttribute("data-type", "not_empty"), i.setAttribute("class", "field-input");
        var d = document.createElement("option");
        for (z = 0; z < l.length; z++) Opt_val = 0 == z ? "" : l[z], (d = document.createElement("option")).appendChild(document.createTextNode(l[z])), d.value = Opt_val, i.appendChild(d);
        let c = elFactory("div", {
            class: "field-container"
        });
        c.appendChild(i), c.appendChild(elFactory("small", {
            class: "field-msg error",
            "data-error": "invalid" + capitalizeFirstLetter("jobselect") + e
        }, "Vælg venligst dit indkomstgrundlag")), n.appendChild(c), n.appendChild(elFactory("div", {
            class: "field-container",
            style: "margin-bottom: 0;margin:0;"
        }, elFactory("label", {
            for: "file" + e,
            id: "filelabel" + e,
            class: "file_label btn btn--main_red fontcolor--white ",
            style: "margin-top: -1px; "
        }, "Upload dokumentation på indkomst"))), n.appendChild(elFactory("div", {
            class: "field-container",
            style: "margin-bottom: 0;margin:0;"
        }, elFactory("input", {
            type: "file",
            name: "file" + e,
            id: "file" + e,
            required: "required",
            accept: ".pdf,.jpg,.png,.jpeg",
            style: "width: 1px; height: 1px; padding: 0; border: none; outline:none; visibility:hidden ",
            class: "file field-input",
            "data-type": "not_empty",
            onchange: 'change_file_button("filelabel' + e + '", "file' + e + '", "Fil er valgt")'
        }), elFactory("small", {
            class: "field-msg error",
            "data-error": "invalid" + capitalizeFirstLetter("file") + e
        }, "Upload af dokumentation er påkrævet"), elFactory("small", {
            class: "field-msg_wrong error",
            "data-error": "wrongformat" + capitalizeFirstLetter("file") + e
        }, "Filer af denne type er ikke godkendt"))), t = "single" == t ? "ansoeger" : t, n.appendChild(elFactory("input", {
            type: "hidden",
            name: "applicant_or_spouse" + e,
            id: "applicant_or_spouse" + e,
            value: t
        }))
    }
    is_single(1)
}
if (document.body.contains(document.getElementById("contacts"))) {
    function addcontact() {
        num_contact += 1;
        let e = document.getElementById("contacts"),
            t = document.createElement("article");
        for (t.setAttribute("class", "contacts"), t.setAttribute("id", "contactwrapper" + num_contact), b = 0; b < contact_list.length; b++) t.appendChild(elFactory("div", {
            class: contact_list[b].class
        }, elFactory("label", {
            for: contact_list[b].name + num_contact
        }, contact_list[b].placeholder), elFactory("div", {
            class: "icon_container"
        }, elFactory("input", {
            type: contact_list[b].type,
            id: contact_list[b].name + num_contact,
            name: contact_list[b].name + num_contact,
            placeholder: contact_list[b].placeholder,
            "data-type": contact_list[b]["data-type"],
            required: contact_list[b].required,
            class: "field-input"
        }), elFactory("i", {
            class: contact_list[b].icon
        })), elFactory("small", {
            class: "field-msg error",
            "data-error": "invalid" + capitalizeFirstLetter(contact_list[b].name) + num_contact
        }, contact_list[b].invalid), elFactory("small", {
            class: "field-msg_wrong error",
            "data-error": "wrongformat" + capitalizeFirstLetter(contact_list[b].name) + num_contact
        }, contact_list[b].wrong)));
        t.appendChild(elFactory("input", {
            type: "hidden",
            name: "contact",
            value: num_contact
        })), t.appendChild(elFactory("i", {
            class: "dashicons dashicons-trash contacts__name__remove",
            onclick: 'removeElement("contactwrapper' + num_contact + '");input_array(0, ' + num_contact + ');change_label("contact")'
        })), e.appendChild(t), change_label("contact")
    }
    num_contact = 0, document.getElementById("add_contact_button").addEventListener("click", function () {
        contact_array.push(num_contact), document.getElementById("cont_arr").value = contact_array
    })
}
if (document.body.contains(document.getElementById("childrens"))) {
    function add_children() {
        num_children += 1, document.body.contains(document.getElementById("add_child_button")) && (document.getElementById("add_child_button").value = "Tilføj endnu et barn");
        let e = document.getElementById("childrens"),
            t = document.createElement("div");
        t.setAttribute("class", "kids kidsaid"), t.setAttribute("id", "kids" + num_children), t.appendChild(elFactory("div", {
            class: " kids kids__age"
        }, elFactory("div", {
            class: "field-container "
        }, elFactory("label", {
            for: "age" + num_children
        }, "Fødselsdato eks. 13-10-2009"), elFactory("div", {
            class: "icon_container"
        }, elFactory("input", {
            type: "date",
            id: "age" + num_children,
            name: "age" + num_children,
            class: "field-input",
            required: "required",
            "data-type": "not_empty"
        }), elFactory("i", {
            class: "fa fa-calendar"
        })), elFactory("small", {
            class: "field-msg error",
            "data-error": "invalid" + capitalizeFirstLetter("age" + num_children)
        }, "Angiv Fødselsdato")))), t.appendChild(elFactory("div", {
            class: " kids kids__gender"
        }, elFactory("div", {
            class: "field-container"
        }, elFactory("label", {
            for: "gender_boy" + num_children
        }, "Dreng", elFactory("input", {
            type: "radio",
            id: "gender_boy" + num_children,
            name: "gender" + num_children,
            value: "boy",
            class: "field-input",
            "data-type": "checkbox",
            required: "required"
        })), elFactory("label", {
            for: "gender_girl" + num_children
        }, "Pige", elFactory("input", {
            type: "radio",
            id: "gender_girl" + num_children,
            name: "gender" + num_children,
            value: "girl",
            class: "field-input",
            "data-type": "checkbox"
        })), elFactory("small", {
            class: "field-msg_wrong error",
            "data-error": "wrongformat" + capitalizeFirstLetter("gender" + num_children)
        }, "Angiv køn ")))), t.appendChild(elFactory("div", {
            class: "kids kids__name"
        }, elFactory("div", {
            class: "field-container "
        }, elFactory("label", {
            for: "name" + num_children
        }, "Navn"), elFactory("div", {
            class: "icon_container"
        }, elFactory("input", {
            type: "text",
            id: "name" + num_children,
            name: "name" + num_children,
            required: "required",
            class: "field-input",
            placeholder: "Navn",
            "data-type": "nonum_text"
        }), elFactory("i", {
            class: "fa fa-user"
        })), elFactory("small", {
            class: "field-msg error",
            "data-error": "invalid" + capitalizeFirstLetter("name" + num_children)
        }, "Navn er påkrævet")))), t.appendChild(elFactory("input", {
            type: "hidden",
            name: "num_children",
            value: num_children
        })), t.appendChild(elFactory("i", {
            class: "dashicons dashicons-trash kids__name__remove",
            onclick: 'removeElement("kids' + num_children + '");input_array(' + num_children + ", 0)"
        })), e.appendChild(t)
    }
    num_children = 0, document.body.contains(document.getElementById("add_child_button")) && document.getElementById("add_child_button").addEventListener("click", function () {
        child_array.push(num_children), document.getElementById("kids_arr").value = child_array
    })
}
if (document.body.contains(document.getElementById("comment"))) {
    document.getElementById("comment").appendChild(elFactory("div", {
        class: "field-container"
    }, elFactory("textarea", {
        id: "comments",
        name: "comments",
        required: "required",
        placeholder: "Skriv her",
        class: "field-input",
        "data-type": "nonum_text",
        rows: 6
    }), elFactory("small", {
        class: "field-msg error",
        "data-error": "invalid" + capitalizeFirstLetter("comments")
    }, "Beskrivelse af problem er påkrævet")))
}

function removeElement(e) {
    var t = document.getElementById(e);
    t.parentNode.removeChild(t)
}

function input_array(e, t) {
    let a = e ? child_array : contact_array,
        n = e || t;
    const r = a.indexOf(n);
    r > -1 && a.splice(r, 1), document.getElementById("kids_arr").value = child_array, document.getElementById("cont_arr").value = contact_array
}

function capitalizeFirstLetter(e) {
    return e.charAt(0).toUpperCase() + e.slice(1)
}

function change_label(e) {
    let t = "";
    switch (e) {
        case "contact":
            t = change_contact_label
    }
    let a = document.querySelectorAll(t[0]).length;
    document.getElementById(t[1]).value = a <= 0 ? t[2] : t[3]
}

function change_file_button(e, t, a) {
    document.getElementById(t) && (document.getElementById(e).innerHTML = a)
}