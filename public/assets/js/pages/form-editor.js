new Quill("#full", {
    bounds: "#full-container .editor",
    modules: {
        toolbar: [
            [{ font: [] }, { size: [] }],
            ["bold", "italic", "underline", "strike"],
            [
                { color: [] },
                { background: [] }
            ],
            [
                { list: "ordered" },
                { list: "bullet" },
                { indent: "-1" },
                { indent: "+1" }
            ],
            ["direction", { align: [] }],
        ]
    },
    theme: "snow"
})
