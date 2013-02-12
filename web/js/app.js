// Global tools
RegExp.escape = function(s) {
  return s.replace(/[-\/\\\^$*+?.()|\[\]{}]/g, '\\$&');
};

var Playlist = Backbone.Collection.extend({
  url: '/index.php/playlist',
  comparator: 'order',
  polling: false,
  pollingInterval: 10,
  initialize: function() {
    this.on('reset', function() {
      this.currentItem = this.first();
    }, this);
    _.bindAll(this, 'executePolling', 'onFetch');
    Backbone.Events.on('song:queue', this.executePolling);
  },
  next: function() {
    var index = this.indexOf(this.currentItem);
    return this.currentItem = this.at(index + 1);
  },
  startPolling: function() {
    this.polling = true;
    this.executePolling();
  },
  stopPolling: function() {
    this.polling = false;
  },
  executePolling: function() {
    this.fetch({ update: true, success: this.onFetch });
  },
  onFetch: function() {
    if (this.polling) {
      setTimeout(this.executePolling, this.pollingInterval * 1000);
    }
  }
});

var Song = Backbone.Model.extend({
  queue: function() {
    var success = function(resp) {
      this.trigger('queued', this, resp, options);
    };
    var error = function(xhr) {
      this.trigger('error', this, xhr, options);
    };
    var options = {
      type: 'POST',
      dataType: 'json',
      url: '/index.php/insert/' + this.id,
      context: this,
      success: success,
      error: error
    };
    $.ajax(options);
  }
});

var SongCollection = Backbone.Collection.extend({
  model: Song,
  url: '/index.php/songs',
  query: function(search) {
    this.fetch({
      data: search
    });
  }
});

var PlaylistView = Backbone.View.extend({
  events: {
    'click li': 'onItemClick'
  },
  initialize: function() {
    this.listenTo(this.collection, 'all', this.render);
  },
  render: function() {
    this.$el.empty();
    this.collection.each(function(item) {
      if (item.get('order') < 0) return;
      this.$el.append(this.template(item.toJSON()));
    }, this);
    return this;
  },
  template: _.template('<li data-id="<%= id %>"><%- song_name %></li>'),
  onItemClick: function(e) {
    var id = $(e.target).attr('data-id');
    var song = this.collection.get(id);
    Backbone.Events.trigger('song:play', song);
  }
});

var SearchView = Backbone.View.extend({
  events: {
    'input #search-field': 'onInput',
    'click .add': 'onAddToPLaylist',
    'click .search-in': 'onPlaceChange'
  },
  initialize: function() {
    _.bindAll(this);
    this.listenTo(this.collection, 'reset', this.render);
    this.searchResults = this.$('#search-results');
    var template = this.$('#search-result-template').html();
    this.template = _.template(template);
  },
  onInput: function(e) {
    this.search = $(e.target).val();
    this.regexp = new RegExp(RegExp.escape(this.search), 'gi');
    this.refresh();
  },
  onPlaceChange: function(e) {
    this.searchIn = $(e.target).attr('data-search-in');
    this.refresh();
  },
  refresh: function() {
    if (!this.search) {
      this.collection.reset();
      return;
    }
    var data = { q: this.search };
    if (this.searchIn) data['in'] = this.searchIn;
    this.collection.query(data);
  },
  hightlight: function(string) {
    return string.replace(this.regexp, '<span class="search-match">$&</span>');
  },
  render: function() {
    this.searchResults.empty();
    this.collection.each(function(item) {
      var context = _.extend(item.toJSON(), { hightlight: this.hightlight });
      this.searchResults.append(this.template(context));
    }, this);
    return this;
  },
  onAddToPLaylist: function(e) {
    e.preventDefault();
    var id = $(e.target).attr('data-id');
    var song = this.collection.get(id);
    song.queue();
    Backbone.Events.trigger('song:queue', song);
  }
});

var PlayerView = Backbone.View.extend({
  initialize: function() {
    this.player = audiojs.create(this.el, {
      trackEnded: this.onTrackEnded,
      loadError: this.onLoadError
    });
    Backbone.Events.on('song:play', function(song) {
      this.playSong(song);
    }, this);
  },
  onTrackEnded: function() {
    Backbone.Events.trigger('player:ended', this);
  },
  onLoadError: function() {
    Backbone.Events.trigger('player:error', this);
  },
  loadSong: function(song, play) {
    var url = song.get('song_media_url');
    this.player.load(url);
    if (play) this.player.play();
  },
  playSong: function(song) {
    this.loadSong(song, true);
  }
});

$(document).ready(function() {
  var playlist = new Playlist();
  playlist.fetch();
  playlist.startPolling();
  playlist.on('reset', function() {
    var song = playlist.first();
    playerView.loadSong(song);
  }, this);
  var playlistView = new PlaylistView({
    el: '#playlist',
    collection: playlist
  });

  var playerView = new PlayerView({
    el: '#player'
  });

  var songs = new SongCollection();
  var searchView = new SearchView({
    el: '#search-view',
    collection: songs
  });
});
