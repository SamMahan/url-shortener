import React, { useState } from "react";
import ReactDOM from "react-dom";
import axios from "axios";
import { Formik, Form, Field, getIn } from "formik";

interface FormValues {
    url: string;
}

interface UrlHashValues {
    url: string;
    updated_at: string;
    created_at: string;
    id: number;
    hash_key: string;
    short_url: string;
}

interface AxiosResponse {
    data: UrlHashValues;
    //this is the only piece of data that I'm really concerned about
}
const UrlForm: React.FC<{}> = () => {
    const initialValues: FormValues = { url: "" };
    // relying purely on the API for validation for this form
    // with more time/requirements I'd implement Yup client-side validation
    const [errors, setErrors] = useState<object>({});
    const [message, setMessage] = useState<JSX.Element>(<></>);
    const [hasError, setHasError] = useState<boolean>(false);
    return (
        <div>
            <h1>My Example</h1>
            <Formik
                initialValues={initialValues}
                onSubmit={(values, actions) => {
                    axios
                        .post("/api/url_hash", values)
                        .then((response: AxiosResponse) => {
                            const data = response.data;
                            const url = data.short_url;
                            setHasError(false);
                            setMessage(
                                <p>
                                    Url is set at <a href={url}>{url}</a>
                                </p>
                            );
                            setErrors({});
                        })
                        .catch((e) => {
                            setHasError(true);
                            let status = e.response.status;
                            if (status != 1422) {
                                setMessage(
                                    <p>
                                        An unknown problem occurred, please
                                        check back later
                                    </p>
                                );
                                // return;
                            }
                            let errors = getIn(e, "response.data.errors");
                            setErrors(errors);
                        });
                    actions.setSubmitting(false);
                }}
            >
                {({ touched, isSubmitting }) => {
                    return (
                        <div className="row">
                            <div className="col-12">
                                <Form className="form">
                                    <div className="form-group">
                                        <label htmlFor="url">url</label>
                                        <Field
                                            className="form-control"
                                            id="url"
                                            name="url"
                                            aria-describedby="url-small"
                                            placeholder="enter a site url"
                                        />
                                        {getIn(errors, "url") && (
                                            <p className="text-danger">
                                                {getIn(errors, "url")}
                                            </p>
                                        )}
                                    </div>
                                    <div className="form-group justify-content-right">
                                        {/* some people like this syntax and some d o n o t */}
                                        {isSubmitting ? (
                                            <button className="btn btn-secondary btn-block">
                                                Submitting...
                                            </button>
                                        ) : (
                                            <button
                                                className="btn btn-primary btn-block"
                                                type="submit"
                                            >
                                                Submit
                                            </button>
                                        )}
                                    </div>
                                </Form>
                            </div>
                            {message && !hasError ? (
                                <div className="col-12 border-color-success">
                                    {message}
                                </div>
                            ) : (
                                <></>
                            )}
                        </div>
                    );
                }}
            </Formik>
        </div>
    );
};

// We only want to try to render our component on pages that have a div with an ID
// of "example"; otherwise, we will see an error in our console
if (document.getElementById("react-component-url-form")) {
    ReactDOM.render(
        <UrlForm />,
        document.getElementById("react-component-url-form")
    );
}
