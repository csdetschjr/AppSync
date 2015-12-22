var AppSyncBox = React.createClass({
    getInitialState: function() {
        return {umbrella: undefined, portal: null, portalMembers: [], errorData: {message: "", location: ""}};
    },
    componentDidMount: function() {
        this.updatePortals();
    },
    setUmbrella: function(newUmbrella) {
        this.setState({umbrella: newUmbrella});
    },
    doSearch: function(datum) {
        this.setState({portal: datum});
        $.ajax({
            url: 'index.php?module=appsync&action=AjaxGetPortalMembers',
            dataType: 'json',
            data: {org_id: datum.id, umbrella: this.state.umbrella},
            success: function(data) {
                this.setState({portalMembers: data})
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }.bind(this)
        });
    },
    updatePortals: function() {
        $.ajax({
            url: 'index.php?module=appsync&action=AjaxUpdateOrgData',
            type: 'POST',
            success: function()
            {
                this.setState({updated: true});
            }.bind(this),
            error: function(xhr, status, err)
            {
                //TODO create error handler
            }.bind(this)
        });
    },
    handleError: function(data)
    {
        this.setState({errorData: data});
    },
    clearError: function()
    {
        this.setState({errorData: {message: "", location: ""}});
    },
    render: function()
    {
        if(this.state.umbrella == undefined)
        {
            portalPick = (<div></div>);
        }
        else
        {
            portalPick = (<div className="form-group">
                <PortalPickBox onSelect={this.doSearch} umbrellaId={this.state.umbrella} refs="portalPickBox"/>
            </div>);
        }
        if(this.state.errorData.message == "")
        {
            errorBox = (<div></div>);
        }
        else
        {
            errorBox = (<ErrorBox errorData={this.state.errorData} />);
        }
        return(
            <div>
                <nav className="navbar navbar-default">
                    <div className="container-fluid">
                        <div className="navbar-header">
                            <button type="button" className="navbar-toggle collapsed" data-toggle="collapse"
                                data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                <span className="sr-only">Toggle navigation</span>
                                <span className="icon-bar"></span>
                                <span className="icon-bar"></span>
                                <span className="icon-bar"></span>
                            </button>
                            <a className="navbar-brand" href="#">AppSync Admin</a>
                        </div>
                        <div className="collapse navbar-collapse">
                            <form className="navbar-form">
                                <div className="form-group">
                                    <UmbrellaPickBox change={this.setUmbrella} />
                                </div>
                                {portalPick}
                            </form>
                        </div>
                    </div>
                </nav>
                {errorBox}
                <PortalBox portal={this.state.portal} clearError={this.clearError} errorHandler={this.handleError}
                    errorData={this.state.errorData} portalMembers={this.state.portalMembers}/>
            </div>
        );
    }
});

var UmbrellaPickBox = React.createClass({
    getInitialState: function()
    {
        return({umbrellas: []});
    },
    componentWillMount: function()
    {
        this.getUmbrellas();
    },
    change: function()
    {
        var uChoice = this.refs.umbrellaChoice.getDOMNode().value;
        this.props.change(uChoice);
    },
    getUmbrellas: function()
    {
        $.ajax({
            url: 'index.php?module=appsync&action=AjaxGetUmbrellaList',
            type: 'GET',
            dataType: 'json',
            success: function(data)
            {
                this.setState({umbrellas: data})
            }.bind(this),
            error: function(xhr, status, err)
            {
                alert(err.toString())
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    render: function()
    {
        var options = Array({umbrella_id: -1, umbrella_name: "Pick an umbrella..."});
        var data = this.state.umbrellas;

        for(i = 0; i < data.length; i++)
        {
            options.push(data[i]);
        }

        var selectOptions = options.map(function(node)
        {
            return(<option value={node.umbrella_id}>{node.umbrella_name}</option>)
        });

        return(
            <div>
                <select onChange={this.change} className="form-control" ref="umbrellaChoice">
                    {selectOptions}
                </select>
            </div>
        );
    }
});

var PortalPickBox = React.createClass({
    componentDidMount: function() {
        var searchSuggestions = new Bloodhound({
            datumTokenizer: function(datum){
                var nameTokens      = Bloodhound.tokenizers.obj.whitespace('name');
                return nameTokens;
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: 'index.php?module=appsync&action=GetSearchSuggestions&umbrellaId=' + this.props.umbrellaId + '&searchString=%QUERY',
                wildcard: '%QUERY'
            }
        });

        var element = ReactDOM.findDOMNode(this);
        $(element).typeahead({
            minLength: 3,
            highlight: true,
            hint: true
        },
        {
            name: 'portals',
            display: 'portalId',
            source: searchSuggestions.ttAdapter(),
            limit: 15,
            templates: {
                suggestion: function(row) {
                    return ('<p>'+row.name+' &middot; ' + row.id + '</p>');
                }
            }
        });

        // Event handler for selecting a suggestion
        var handleSearch = this.props.onSelect;
        $(element).bind('typeahead:select', function(obj, datum, name) {
            // Redirect to the student profile the user selected
            handleSearch(datum);
        });

        // Event handler for enter key.. Search with whatever the person put in the box
        $(element).keydown(function(e){

            // Look for the enter key
            if(e.keyCode == 13) {
                // Prevent default to keep the form from being submitted on enter
                e.preventDefault();
                return;
            }

            // Ignore the tab key
            if(e.keyCode == 9){
                return;
            }

        });
    },
    componentDidUpdate: function() {
        var element = ReactDOM.findDOMNode(this);
        $(element).typeahead('destroy');
        var searchSuggestions = new Bloodhound({
            datumTokenizer: function(datum){
                var nameTokens      = Bloodhound.tokenizers.obj.whitespace('name');
                return nameTokens;
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: 'index.php?module=appsync&action=GetSearchSuggestions&umbrellaId=' + this.props.umbrellaId + '&searchString=%QUERY',
                wildcard: '%QUERY'
            }
        });

        var element = ReactDOM.findDOMNode(this);
        $(element).typeahead({
            minLength: 3,
            highlight: true,
            hint: true
        },
        {
            name: 'portals',
            display: 'portalId',
            source: searchSuggestions.ttAdapter(),
            limit: 15,
            templates: {
                suggestion: function(row) {
                    return ('<p>'+row.name+' &middot; ' + row.id + '</p>');
                }
            }
        });

        // Event handler for selecting a suggestion
        var handleSearch = this.props.onSelect;
        $(element).bind('typeahead:select', function(obj, datum, name) {
            // Redirect to the student profile the user selected
            handleSearch(datum);
        });

        // Event handler for enter key.. Search with whatever the person put in the box
        $(element).keydown(function(e){

            // Look for the enter key
            if(e.keyCode == 13) {
                // Prevent default to keep the form from being submitted on enter
                e.preventDefault();
                return;
            }

            // Ignore the tab key
            if(e.keyCode == 9){
                return;
            }

        });
    },
    componentWillUnmount: function() {
        var element = ReactDOM.findDOMNode(this);
        $(element).typeahead('destroy');
    },
    render: function()
    {
        return (
            <input type="search" name="portalId" id="portalSearch" className="form-control typeahead" placeholder="Portal Name" ref="searchString" autoComplete="off" autofocus/>
        );
    }
});

var ErrorBox = React.createClass({
    render: function()
    {
        return (
            <div className="alert alert-danger" role="alert">
                <i className="fa fa-times fa-2x"></i> <span>{this.props.errorData.message}</span>
            </div>
        );
    }
});


ReactDOM.render(
    <AppSyncBox/>,
    document.getElementById('org')
);
