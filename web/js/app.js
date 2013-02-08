// Global tools
RegExp.escape = function(s) {
  return s.replace(/[-\/\\\^$*+?.()|\[\]{}]/g, '\\$&');
};

var Playlist = Backbone.Collection.extend({
  url: '/index.php/playlist',
  comparator: 'order',
  initialize: function() {
    this.on('reset', function() {
      this.currentItem = this.first();
    }, this);
  },
  next: function() {
    var index = this.indexOf(this.currentItem);
    return this.currentItem = this.at(index + 1);
  }
});

var SongCollection = Backbone.Collection.extend({
  url: '/index.php/songs',
  query: function(search) {
    this.fetch({
      data: { q: search }
    });
  }
});

var PlaylistView = Backbone.View.extend({
  events: {
    'click li': 'onItemClick'
  },
  initialize: function() {
    this.listenTo(this.collection, 'reset', this.render);
  },
  render: function() {
    this.$el.empty();
    this.collection.each(function(item) {
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
    'input #search-field': 'search',
    'click .add': 'onSongAdd'
  },
  initialize: function() {
    _.bindAll(this);
    this.listenTo(this.collection, 'reset', this.render);
    this.searchField = this.$('#search-field');
    this.searchResults = this.$('#search-results');
    var template = this.$('#search-result-template').html();
    this.template = _.template(template);
  },
  search: function() {
    var search = this.searchField.val();
    if (!search) {
      this.collection.reset();
      return;
    }
    this.collection.query(search);
    this.regexp = new RegExp(RegExp.escape(search), 'gi');
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
  onSongAdd: function(e) {
    var id = $(e.target).attr('data-id');
    var song = this.collection.get(id);
    Backbone.Events.trigger('song:queue', song);
  }
});

$(document).ready(function() {
  var playlist = new Playlist();
  playlist.fetch();
  playlist.on('reset', function() {
    var song = playlist.first();
    loadSong(song);
  }, this);

  function loadSong(song, play) {
    var url = song.get('song_media_url');
    console.log('Loading', song.get('song_name'), url);
    player.load(url);
    if (play) player.play();
  }

  var playerEl = $('#player').get(0);
  var player = audiojs.create(playerEl, {
    trackEnded: function() {
      console.log('Track ended');
      var next = playlist.next();
      loadSong(next, true);
    // },
    // loadError: function() {
    //   console.log('Load error');
    }
  });
  var playlistView = new PlaylistView({
    el: '#playlist',
    collection: playlist
  });

  Backbone.Events.on('song:play', function(song) {
    loadSong(song, true);
  });
  Backbone.Events.on('song:queue', function(song) {
    loadSong(song, true);
  });

  var songs = new SongCollection();
  var searchView = new SearchView({
    el: '#search-view',
    collection: songs
  });
});
