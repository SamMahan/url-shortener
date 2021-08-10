import React, { useState } from "react";
import ReactDOM from "react-dom";
import axios from "axios";

import { useTable } from "react-table";

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
    times_accessed: number;
}

interface AxiosResponse {
    data: { data: Array<UrlHashValues> };
}

const UrlList: React.FC<{}> = () => {
    const [urlList, setUrlList] = useState<Array<UrlHashValues>>([]);
    const [loadAttempts, setLoadAttempts] = useState<number>(0);
    const [hasError, setHasError] = useState<boolean>(false);
    const [isLoading, setIsLoading] = useState<boolean>(false);

    const getUrlList = async () => {
        setIsLoading(true);
        setLoadAttempts(loadAttempts + 1);
        axios
            .get("/api/url_hash/popular")
            .then((response: AxiosResponse) => {
                setHasError(false);
                setUrlList(response.data.data);
                setIsLoading(false);
            })
            .catch((e) => {
                setLoadAttempts(loadAttempts + 1);
                setHasError(true);
                setUrlList([]);
                setIsLoading(false);
            });
    };

    if (!loadAttempts && !hasError && !isLoading) getUrlList();
    // let content = <></>;
    let content = (
        <Table urlList={urlList} key={JSON.stringify(urlList)}></Table>
    );
    if (isLoading) {
        content = <p>Loading...</p>;
    } else if (hasError) {
        content = (
            <p className="text-danger">
                {" "}
                There was a problem loading the top Urls
            </p>
        );
    } else if (urlList.length < 0) {
        content = <p>No current urls found</p>;
        // content = <></>;
    }
    return <div className={"col-12"}>{content}</div>;
};

interface TableInput {
    urlList: Array<UrlHashValues>;
}

function Table(input: TableInput): JSX.Element {
    const urlList = input.urlList;
    let trimmedUrlList: Array<object> = [];
    for (let index in urlList) {
        let indexObj = urlList[index];
        trimmedUrlList.push({
            short_url: indexObj.short_url,
            url: indexObj.url,
            times_accessed: indexObj.times_accessed,
        });
    }

    let data = React.useMemo(() => trimmedUrlList, []);

    const columns = React.useMemo(
        () => [
            {
                Header: "Url",
                accessor: "url", // accessor is the "key" in the data
            },
            {
                Header: "Short Url",
                accessor: "short_url",
                //@ts-ignore
                Cell: ({ cell }) => {
                    //@ts-ignore
                    return <a href={cell.value}>{cell.value}</a>;
                },
            },
            {
                Header: "Times Accessed",
                accessor: "times_accessed",
            },
        ],
        []
    );

    const tableInstance = useTable({ columns, data });
    const { getTableProps, getTableBodyProps, headerGroups, rows, prepareRow } =
        tableInstance;
    let urlListKey = JSON.stringify(trimmedUrlList);
    return (
        <>
            <h2>Top {urlList.length} Urls</h2>
            <h3>Sorted by times accessed</h3>
            <h4>
                {" "}
                this counts the number of urls provided by the system, which is
                limited to 100 server-side
            </h4>
            {/* // apply the table props */}
            <table className="table" {...getTableProps()} key={urlListKey}>
                <thead>
                    {
                        // Loop over the header rows
                        headerGroups.map((headerGroup) => (
                            // Apply the header row props
                            <tr {...headerGroup.getHeaderGroupProps()}>
                                {
                                    // Loop over the headers in each row
                                    headerGroup.headers.map((column) => (
                                        // Apply the header cell props
                                        <th {...column.getHeaderProps()}>
                                            {
                                                // Render the header
                                                column.render("Header")
                                            }
                                        </th>
                                    ))
                                }
                            </tr>
                        ))
                    }
                </thead>
                {/* Apply the table body props */}
                <tbody {...getTableBodyProps()}>
                    {
                        // Loop over the table rows
                        rows.map((row) => {
                            // Prepare the row for display
                            prepareRow(row);
                            return (
                                // Apply the row props
                                <tr {...row.getRowProps()}>
                                    {
                                        // Loop over the rows cells
                                        row.cells.map((cell) => {
                                            // Apply the cell props
                                            return (
                                                <td {...cell.getCellProps()}>
                                                    {
                                                        // Render the cell contents
                                                        cell.render("Cell")
                                                    }
                                                </td>
                                            );
                                        })
                                    }
                                </tr>
                            );
                        })
                    }
                </tbody>
            </table>
        </>
    );
}

// We only want to try to render our component on pages that have a div with an ID
// of "example"; otherwise, we will see an error in our console
if (document.getElementById("react-component-url-list")) {
    ReactDOM.render(
        <UrlList />,
        document.getElementById("react-component-url-list")
    );
}
